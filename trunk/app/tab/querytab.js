Ext.ns('monoql.tab');
monoql.tab.querytab = function() {
	var cls = 'monoql-tab-querytab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Query',
		layout:'border',
		border:false,
		closable:true,
		initComponent: function() {
			this.title = 'Query' + (Ext.isNumber(this.index) ? ' ' + this.index : '');
			this.queryform = new monoql.form.queryform();
			this.resulttabset = new monoql.tab.resulttabset();
			this.items = [{
				region:'north',
				layout:'fit',
				split:true,
				height:100,
				border:false,
				items:[this.queryform]
			},{
				region:'center',
				border:false,
				bodyStyle:'border-top-width:1px;',
				items:[this.resulttabset]
			}];
			this.queryform.getForm().on('actioncomplete', this.onQueryFormActionComplete, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryFormActionComplete:function(form, action) {
			if (action.type=="submit") {
				this.onQueryFormSubmitComplete(form, action);
			}
		},
		onQueryFormSubmitComplete:function(form, action) {
			alert(Ext.pluck(action.result.rows, "username"));
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();