Ext.ns('monoql.menu');
monoql.menu.helpmenu = function() {
	var cls = 'monoql-menu-helpmenu';
	var Class = Ext.extend(monoql.menu.menu, {
		initComponent:function() {
			this.items = [{
				text:'Some Item'
			}];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();