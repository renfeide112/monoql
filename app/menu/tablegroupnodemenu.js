Ext.ns('monoql.menu');
monoql.menu.tablegroupnodemenu = function() {
	var cls = 'monoql-menu-tablegroupnodemenu';
	var Class = Ext.extend(monoql.menu.nodemenu, {
		initComponent:function() {
			this.query = new monoql.menu.item({
				text:'New Query',
				iconCls:'monoql-menu-item-query-icon'
			});
			this.create = new monoql.menu.item({
				text:'Create Table',
				iconCls:'monoql-menu-item-createtable-icon'
			});
			this.dropall = new monoql.menu.item({
				text:'Drop All Tables',
				iconCls:'monoql-menu-item-dropalltables-icon'
			});
			this.emptyall = new monoql.menu.item({
				text:'Empty All Tables',
				iconCls:'monoql-menu-item-emptyalltables-icon'
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.add([this.query, this.create, this.emptyall, this.dropall, this.refresh]);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();