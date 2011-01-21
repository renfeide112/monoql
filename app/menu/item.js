Ext.ns('monoql.menu');
monoql.menu.item = function() {
	var cls = 'monoql-menu-item';
	var Class = Ext.extend(Ext.menu.Item, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();