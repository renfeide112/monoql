Ext.ns('monoql.menu');
monoql.menu.functionnodemenu = function() {
	var cls = 'monoql-menu-functionnodemenu';
	var Class = Ext.extend(monoql.menu.nodemenu, {
		initComponent:function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.add([this.refresh]);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();