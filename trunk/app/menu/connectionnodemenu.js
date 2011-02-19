Ext.ns('monoql.menu');
monoql.menu.connectionnodemenu = function() {
	var cls = 'monoql-menu-connectionnodemenu';
	var Class = Ext.extend(monoql.menu.nodemenu, {
		initComponent:function() {
			this.modify = new monoql.menu.item({
				text:'Modify',
				iconCls:'monoql-menu-item-modifyconnection-icon'
			});
			this.remove = new monoql.menu.item({
				text:'Remove',
				iconCls:'monoql-menu-item-removeconnection-icon'
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
			this.add([this.modify, this.remove, this.refresh]);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();