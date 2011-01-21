Ext.ns('monoql.tree');
monoql.tree.tree = function() {
	var cls = 'monoql-tree-tree';
	var Class = Ext.extend(Ext.tree.TreePanel, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();