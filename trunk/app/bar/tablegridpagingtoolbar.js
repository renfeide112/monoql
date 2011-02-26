Ext.ns('monoql.bar');
monoql.bar.tablegridpagingtoolbar = function() {
	var cls = 'monoql-bar-tablegridpagingtoolbar';
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