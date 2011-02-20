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
			this.querytextarea = new Ext.form.TextArea({
				name:'queries',
				hideLabel:true,
				anchor:'0 0',
				style:{
					'border-width':'0px',
					'padding':'',
					'padding-left':'2px',
					'font-size':'12px',
					'line-height':'15px',
					'font-family':'Verdana'
				}
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
			}]);
		},
		onQueryFormCtrlEnter:function(key, e) {
			var query = this.querytextarea.getSelectedText() || this.querytextarea.getValue();
			if (query.trim()) {
				monoql.direct.Query.execute(query, this.tab.connection.get('id'), this.onQueryResult.createDelegate(this));
			}
		},
		onQueryResult:function(result, response) {
			this.tab.resulttabset.resulttab.grid.store.reader.readRecords(result);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();