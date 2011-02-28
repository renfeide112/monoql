Ext.ns('monoql.tree');
monoql.tree.sprocnode = function() {
	var cls = 'monoql-tree-sprocnode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			Ext.apply(attributes, {
				iconCls:cls + '-icon'
			});
			this.menu = new monoql.menu.sprocnodemenu({
				node:this
			});
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();