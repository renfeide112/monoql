Ext.ns('monoql.tab');
monoql.tab.messagetab = function() {
	var cls = 'monoql-tab-messagetab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Messages',
		layout:'fit',
		border:false,
		initComponent: function() {
			this.getQueryForm().on({
				scope:this,
				query:this.onQueryFormQuery,
				queryresult:this.onQueryFormQueryResult
			});
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryFormQueryResult:function(form, query, connection, records) {
			var json = this.getResultGrid().getStore().reader.jsonData
			this.update(json.message);
			if (!json.success) {
				this.tabset.activate(this);
			}
		},
		updateMessage:function(message) {
		},
		onQueryFormQuery:function(form, query, connection) {
			if (this.rendered) {
				this.update('');
			}
		},
		getQueryForm:function() {
			return this.getQueryTab().queryform;
		},
		getResultGrid:function() {
			return this.tabset.resulttab.grid;
		},
		getQueryTab:function() {
			return this.tabset.tab;
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();