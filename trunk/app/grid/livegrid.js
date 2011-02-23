Ext.ns('monoql.grid');
monoql.grid.livegrid = function() {
	var cls = 'monoql-grid-livegrid';
	var Class = Ext.extend(Ext.ux.grid.livegrid.GridPanel, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();