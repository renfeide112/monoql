Ext.ns('monoql.grid');
monoql.grid.resultgrid = function() {
	var cls = 'monoql-grid-resultgrid';
	var Class = Ext.extend(monoql.grid.grid, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();