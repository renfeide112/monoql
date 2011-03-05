Ext.ns('monoql.menu');
monoql.menu.tablenodemenu = function() {
	var cls = 'monoql-menu-tablenodemenu';
	var Class = Ext.extend(monoql.menu.nodemenu, {
		initComponent:function() {
			this.query = new monoql.menu.item({
				text:'New Query',
				iconCls:'monoql-menu-item-query-icon'
			});
			this.open = new monoql.menu.item({
				text:'View Data',
				iconCls:'monoql-menu-item-tabledata-icon'
			});
			this.modify = new monoql.menu.item({
				text:'Modify',
				iconCls:'monoql-menu-item-modifytable-icon'
			});
			this.empty = new monoql.menu.item({
				text:'Empty',
				iconCls:'monoql-menu-item-emptytable-icon'
			});
			this.drop = new monoql.menu.item({
				text:'Drop',
				iconCls:'monoql-menu-item-droptable-icon'
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.add([this.query, this.open, this.modify, this.empty, this.drop, this.refresh]);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();