Ext.ns('monoql.tree');
monoql.tree.connectionnode = function() {
	var cls = 'monoql-tree-connectionnode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			Ext.apply(attributes, {
				iconCls:cls + '-icon'
			});
			this.menu = new monoql.menu.connectionnodemenu({
				node:this
			});
			this.menu.modify.on('click', this.onMenuModifyClick, this);
			this.menu.remove.on('click', this.onMenuRemoveClick, this);
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onMenuModifyClick:function(item, event) {
			
		},
		onMenuRemoveClick:function(item, event) {
			
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();