Ext.ns('monoql.grid');
monoql.grid.grid = function() {
	var cls = 'monoql-grid-grid';
	var Class = Ext.extend(Ext.grid.GridPanel, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();