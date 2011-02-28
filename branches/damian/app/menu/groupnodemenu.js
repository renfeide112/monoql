Ext.ns('monoql.menu');
monoql.menu.groupnodemenu = function() {
	var cls = 'monoql-menu-groupnodemenu';
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