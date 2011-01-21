Ext.ns('monoql.tree');
monoql.tree.node = function() {
	var cls = 'monoql-tree-node';
	var Class = Ext.extend(Ext.tree.AsyncTreeNode, {
		constructor: function(attributes) {
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
			if (this.menu && this.menu.refresh) {
				this.menu.refresh.addListener('click', this.onMenuRefreshClick, this);
			}
			this.addListener('contextmenu', this.onNodeContextMenu, this);
		},
		onMenuRefreshClick:function(item, event) {
			if (Ext.isFunction(this.reload)) {
				this.reload();
			}
		},
		onNodeContextMenu:function(node, event) {
			node.select();
			if (node.menu && node.menu.items) {
				var pos = event.getXY();
				pos[0] -= 15;
				node.menu.showAt(pos);
			}
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();