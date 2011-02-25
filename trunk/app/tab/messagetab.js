Ext.ns('monoql.tab');
monoql.tab.messagetab = function() {
	var cls = 'monoql-tab-messagetab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Messages',
		layout:'fit',
		border:false,
		initComponent: function() {
			this.getQueryForm().on('query', this.onQueryFormQuery, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryFormQuery:function(form, query, connection) {
			if (this.rendered) {
				this.update('');
			}
		},
		getQueryForm:function() {
			return this.tabset.tab.queryform;
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();