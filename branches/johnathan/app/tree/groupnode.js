Ext.ns('monoql.tree');
monoql.tree.groupnode = function() {
	var cls = 'monoql-tree-groupnode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();