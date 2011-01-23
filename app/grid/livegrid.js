Ext.ns('monoql.grid');
monoql.grid.livegrid = function() {
	var cls = 'monoql-grid-livegrid';
	var Class = Ext.extend(monoql.grid.grid, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();