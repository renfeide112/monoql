Ext.ns('monoql.menu');
monoql.menu.menu = function() {
	var cls = 'monoql-menu-menu';
	var Class = Ext.extend(Ext.menu.Menu, {
		defaultType:'monoql-menu-item',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();