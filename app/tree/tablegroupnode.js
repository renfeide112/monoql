Ext.ns('monoql.tree');
monoql.tree.tablegroupnode = function() {
	var cls = 'monoql-tree-tablegroupnode';
	var Class = Ext.extend(monoql.tree.groupnode, {
		constructor: function(attributes) {
			this.menu = new monoql.menu.tablegroupnodemenu({
				node:this
			});
			this.menu.query.on('click', this.onMenuQueryClick, this);
			Class.superclass.constructor.call(this, attributes);
			this.on('beforeload', this.onTableGroupNodeBeforeLoad, this);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onMenuQueryClick:function(item, e) {
			this.menu.hide();
			ui.tabs.addQueryTab(this.getConnection(), this.getDatabase());
		},
		onTableGroupNodeBeforeLoad:function(node) {
			Ext.apply(node.getOwnerTree().getLoader().baseParams, {
				database:node.getDatabase()
			});
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();