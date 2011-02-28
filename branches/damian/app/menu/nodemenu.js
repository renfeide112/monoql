Ext.ns('monoql.menu');
monoql.menu.nodemenu = function() {
	var cls = 'monoql-menu-nodemenu';
	var Class = Ext.extend(monoql.menu.menu, {
		initComponent: function() {
			this.refresh = new monoql.menu.item({
				text:'Refresh',
				iconCls:'monoql-menu-item-refresh-icon'
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();