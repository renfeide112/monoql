Ext.ns('monoql.button');
monoql.button.addquerytabbutton = function() {
	var cls = 'monoql-button-addquerytabbutton';
	
	var Menu = Ext.extend(monoql.menu.menu, {
		initComponent:function() {
			this.on({
				scope:this,
				beforeshow:this.onAddQueryTabButtonMenuBeforeShow,
				afterrender:this.onAddQueryTabButtonMenuAfterRender,
				itemclick:this.onAddQueryTabButtonMenuItemClick
			});
			Menu.superclass.initComponent.call(this);
			this.addClass(cls + '-menu');
		},
		onAddQueryTabButtonMenuItemClick:function(item, e) {
			if (item.connection) {
				ui.tabs.addQueryTab(item.connection);
			}
		},
		onAddQueryTabButtonMenuBeforeShow:function(menu) {
			return this.items.getCount()>0;
		},
		onAddQueryTabButtonMenuAfterRender:function(menu) {
			this.addItemsFromConnectionStore(ui.connectionstore.getRange());
			ui.connectionstore.on({
				scope:this,
				add:this.onConnectionStoreAdd,
				remove:this.onConnectionStoreRemove,
				update:this.onConnectionStoreUpdate
			});
		},
		addItemsFromConnectionStore:function(records) {
			Ext.each(records, function(item, i, items) {
				this.add(new monoql.menu.item({
					connection:item,
					text:item.get('name')
				}));
			}, this);
		},
		onConnectionStoreAdd:function(store, records, index) {
			this.addItemsFromConnectionStore(records);
		},
		onConnectionStoreRemove:function(store, record, index) {
			this.items.each(function(item, index, length) {
				if (item.connection===record) {
					this.remove(item);
				}
			}, this);
		},
		onConnectionStoreUpdate:function(store, record, index) {
		
		}
	});
	
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Add a new query tab',
		menu:new Menu(),
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();