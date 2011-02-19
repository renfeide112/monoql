Ext.ns('monoql.tab');
monoql.tab.querytab = function() {
	var cls = 'monoql-tab-querytab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Query',
		layout:'border',
		border:false,
		closable:true,
		constructor:function(config) {
			this.addEvents('connectionchange');
			Class.superclass.constructor.call(this, config);
		},
		initComponent: function() {
			this.title = 'Query' + (Ext.isNumber(this.index) ? ' ' + this.index : '');
			this.bbar = new monoql.bar.querytabstatusbar({
				querytab:this
			});
			this.queryform = new monoql.form.queryform({
				region:'north',
				tab:this,
				height:200,
				split:true
			});
			this.resulttabset = new monoql.tab.resulttabset({
				region:'center',
				split:true,
				border:false,
				bodyStyle:'border-top-width:1px;'
			});
			ui.toolbar.connectioncombobox.on('select', this.onToolBarConnectionComboBoxSelect, this);
			this.queryform.getForm().on('actioncomplete', this.onQueryFormActionComplete, this);
			this.items = [this.queryform, this.resulttabset];
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
		},
		isActive:function() {
			return this.ownerCt && this.ownerCt.getActiveTab()==this;
		},
		setConnection:function(connection) {
			var oldConn = this.connection,
				newConn = Ext.isNumber(parseInt(connection)) ? ui.connectionstore.getById(connection) : connection;
			if (newConn) {
				if (!oldConn || newConn.get('id')!=oldConn.get('id')) {
					this.fireEvent('connectionchange', this, oldConn, newConn);
				}
				this.connection = newConn;
			}
			return this.connection;
		},
		onToolBarConnectionComboBoxSelect:function(combo, record, index) {
			if (this.isActive()) {
				this.setConnection(combo.getValue());
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();