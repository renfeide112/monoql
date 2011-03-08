Ext.ns('monoql.menu');
monoql.menu.filemenu = function() {
	var cls = 'monoql-menu-filemenu';
	var Class = Ext.extend(monoql.menu.menu, {
		initComponent:function() {
			this.newquery = new monoql.menu.item({
				text:'New Query',
				iconCls:cls + '-newquery'
			});
			this.open = new monoql.menu.item({
				text:'Open',
				iconCls:cls + '-open'
			});
			this.save = new monoql.menu.item({
				text:'Save',
				iconCls:cls + '-save'
			});
			this.saveas = new monoql.menu.item({
				text:'Save As',
				iconCls:cls + '-saveas'
			});
			this.items = [this.newquery, this.open, this.save, this.saveas];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();