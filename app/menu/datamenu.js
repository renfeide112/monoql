Ext.ns('monoql.menu');
monoql.menu.datamenu = function() {
	var cls = 'monoql-menu-datamenu';
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