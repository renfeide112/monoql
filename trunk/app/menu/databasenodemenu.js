Ext.ns('monoql.menu');
monoql.menu.databasenodemenu = function() {
	var cls = 'monoql-menu-databasenodemenu';
	var Class = Ext.extend(monoql.menu.nodemenu, {
		initComponent:function() {
			this.query = new monoql.menu.item({
				text:'New Query',
				iconCls:'monoql-menu-item-query-icon'
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.add([this.query, this.refresh]);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();