Ext.ns('monoql.tree');
monoql.tree.viewgroupnode = function() {
	var cls = 'monoql-tree-viewgroupnode';
	var Class = Ext.extend(monoql.tree.groupnode, {
		constructor: function(attributes) {
			this.menu = new monoql.menu.viewgroupnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.on('beforeload', this.onViewGroupNodeBeforeLoad, this);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onViewGroupNodeBeforeLoad:function(node) {
			Ext.apply(node.getOwnerTree().getLoader().baseParams, {
				database:node.getDatabase()
			});
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();