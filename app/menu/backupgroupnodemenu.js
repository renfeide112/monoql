Ext.ns('monoql.menu');
monoql.menu.backupgroupnodemenu = function() {
	var cls = 'monoql-menu-backupgroupnodemenu';
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