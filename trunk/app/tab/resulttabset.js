Ext.ns('monoql.tab');
monoql.tab.resulttabset = function() {
	var cls = 'monoql-tab-resulttabset';
	var Class = Ext.extend(monoql.tab.tabset, {
		activeTab:0,
		border:false,
		initComponent: function() {
			this.resulttab = new monoql.tab.resulttab({tabset:this});
			this.messagetab = new monoql.tab.messagetab({tabset:this});
			this.resulttab.grid.store.on('load', this.onResultTabGridStoreLoad, this);
			this.tab.queryform.on('query', this.onQueryFormQuery, this);
			this.items = [this.resulttab, this.messagetab];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryFormQuery:function(queryform, query, connection) {
			this.activate(this.resulttab);
		},
		onResultTabGridStoreLoad:function(store, records, options) {
			var rows = store.reader.jsonData.rows,
				message = store.reader.jsonData.message
			if (!rows || rows.length===0) {
				this.activate(this.messagetab);
				this.messagetab.update(message);
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();