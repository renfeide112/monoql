Ext.ns('monoql.form');
monoql.form.queryform = function() {
	var cls = 'monoql-form-queryform';
	var Class = Ext.extend(monoql.form.form, {
		defaults:{},
		bodyStyle:{
			'border-top-width':'0px',
			'border-left-width':'0px',
			'border-right-width':'0px'
		},
		initComponent: function() {
			this.addEvents('beforequery', 'query', 'queryresult', 'cancelquery');
			this.querytextarea = new Ext.form.TextArea({
				name:'queries',
				hideLabel:true,
				anchor:'0 0'
			});
			this.queryfield = new Ext.form.Hidden({
				name:'query'
			});
			this.querytextarea.on('render', this.onQueryTextAreaRender, this);
			this.items = [this.querytextarea, this.queryfield];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryTextAreaRender:function(textarea) {
			this.keyMap = new Ext.KeyMap(this.el, [{
				key:Ext.EventObject.ENTER,
				ctrl:true,
				handler:this.onQueryFormCtrlEnter,
				stopEvent:true,
				scope:this
			},{
				key:Ext.EventObject.F9,
				ctrl:false,
				handler:this.onQueryFormF9,
				stopEvent:true,
				scope:this
			}]);
		},
		onQueryFormCtrlEnter:function(key, e) {
			this.executeQuery();
		},
		onQueryFormF9:function(key, e) {
			this.executeQuery();
		},
		executeQuery:function() {
			var query = this.querytextarea.getSelectedText() || this.querytextarea.getValue();
			if (this.fireEvent('beforequery', this, query, this.tab.connection) !== false) {
				if (query.trim()) {
					monoql.direct.Query.execute(query, this.tab.connection.id, this.tab.database, this.onQueryResult.createDelegate(this, [query, this.tab.connection], true));
					this.fireEvent('query', this, query, this.tab.connection);
				}
			}
		},
		onQueryResult:function(result, response, query, connection) {
			// A cancelled query just sets the cancelled property of the tab to true
			// since there is no way in Ext to abort a DirectProxy request -- so the
			// response will arrive but get ignored
			if (this.tab.cancelled) {
				this.tab.cancelled = false;
			} else {
				this.tab.resulttabset.resulttab.grid.store.loadData(result);
				this.fireEvent('queryresult', this, query, connection, result);
			}
		},
		cancelQuery:function() {
			this.fireEvent('cancelquery', this, this.tab.connection);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();