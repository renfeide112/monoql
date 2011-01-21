Ext.ns('monoql.tree');
monoql.tree.triggergroupnode = function() {
	var cls = 'monoql-tree-triggergroupnode';
	var Class = Ext.extend(monoql.tree.groupnode, {
		constructor:function(attributes) {
			this.menu = new monoql.menu.triggergroupnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();