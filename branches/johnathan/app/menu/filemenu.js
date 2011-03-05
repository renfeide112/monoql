Ext.ns('monoql.menu');
monoql.menu.filemenu = function() {
	var cls = 'monoql-menu-filemenu';
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