Ext.ns('monoql.tree');
monoql.tree.scriptnode = function() {
	var cls = 'monoql-tree-scriptnode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			this.menu = new monoql.menu.scriptnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();