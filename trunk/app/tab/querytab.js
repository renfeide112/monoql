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
			this.bbar = new monoql.bar.querytabstatusbar({
				querytab:this
			});
			this.queryform = new monoql.form.queryform({
				region:'center',
				connection:this.connection,
				height:200
			});
			this.resulttabset = new monoql.tab.resulttabset({
				region:'south',
				split:true,
				height:0,
				collapseMode:'mini',
				animCollapse:false,
				collapsed:true,
				border:false,
				bodyStyle:'border-top-width:1px;'
			});
			this.resulttabset.on('expand', this.onResultTabSetExpand, this);
			this.items = [this.queryform, this.resulttabset];
			this.queryform.getForm().on('actioncomplete', this.onQueryFormActionComplete, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onResultTabSetExpand:function(panel) {
			panel.setHeight(this.getHeight()-Ext.value(this.queryform.height, 0));
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