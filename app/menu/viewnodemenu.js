Ext.ns('monoql.menu');
monoql.menu.viewnodemenu = function() {
	var cls = 'monoql-menu-viewnodemenu';
	var Class = Ext.extend(monoql.menu.nodemenu, {
		initComponent:function() {
			this.open = new monoql.menu.item({
				text:'View Data',
				iconCls:'monoql-menu-item-viewdata-icon'
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.add([this.open, this.refresh]);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();