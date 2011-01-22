Ext.ns('monoql.form');
monoql.form.queryform = function() {
	var cls = 'monoql-form-queryform';
	var Class = Ext.extend(monoql.form.form, {
		bodyStyle:{
			'border-top-width':'0px',
			'border-left-width':'0px',
			'border-right-width':'0px'
		},
		initComponent: function() {
			this.querytextarea = new Ext.form.TextArea({
				hideLabel:true,
				anchor:'0 0',
				enableKeyEvents:true,
				style:{
					'border-width':'0px'
				}
			});
			this.querytextarea.on('render', this.onQueryTextAreaRender, this);
			this.items = [this.querytextarea];
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
			alert(query);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();