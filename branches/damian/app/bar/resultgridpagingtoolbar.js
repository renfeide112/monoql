Ext.ns('monoql.bar');
monoql.bar.resultgridpagingtoolbar = function() {
	var cls = 'monoql-bar-resultgridpagingtoolbar';
	var Class = Ext.extend(Ext.ux.grid.livegrid.Toolbar, {
		initComponent: function() {
			Ext.applyIf(this, {
				view:this.grid.view,
				displayInfo:true,
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();