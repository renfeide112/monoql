Ext.ns('monoql.tree');
monoql.tree.sprocgroupnode = function() {
	var cls = 'monoql-tree-sprocgroupnode';
	var Class = Ext.extend(monoql.tree.groupnode, {
		constructor: function(attributes) {
			this.menu = new monoql.menu.sprocgroupnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.on('beforeload', this.onSprocGroupNodeBeforeLoad, this);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onSprocGroupNodeBeforeLoad:function(node) {
			Ext.apply(node.getOwnerTree().getLoader().baseParams, {
				database:node.getDatabase()
			});
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();