Ext.ns('monoql.tree');
monoql.tree.triggergroupnode = function() {
	var cls = 'monoql-tree-triggergroupnode';
	var Class = Ext.extend(monoql.tree.groupnode, {
		constructor:function(attributes) {
			this.menu = new monoql.menu.triggergroupnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.on('beforeload', this.onTriggerGroupNodeBeforeLoad, this);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onTriggerGroupNodeBeforeLoad:function(node) {
			Ext.apply(node.getOwnerTree().getLoader().baseParams, {
				database:node.getDatabase()
			});
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();