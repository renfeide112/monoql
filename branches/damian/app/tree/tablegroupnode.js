Ext.ns('monoql.tree');
monoql.tree.tablegroupnode = function() {
	var cls = 'monoql-tree-tablegroupnode';
	var Class = Ext.extend(monoql.tree.groupnode, {
		constructor: function(attributes) {
			this.menu = new monoql.menu.tablegroupnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.on('beforeload', this.onTableGroupNodeBeforeLoad, this);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
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