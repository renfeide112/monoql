Ext.ns('monoql.bar');
monoql.bar.querytabstatusbar = function() {
	var cls = 'monoql-bar-querytabstatusbar';
	var Class = Ext.extend(monoql.bar.toolbar, {
		initComponent: function() {
			this.connectionstatusdisplayfield = new Ext.form.DisplayField({
				value:'Connection Status...'
			});
			this.hoststatusdisplayfield = new Ext.form.DisplayField({
				value:'Host Info...'
			});
			this.userstatusdisplayfield = new Ext.form.DisplayField({
				value:'User Info...'
			});
			this.databasestatusdisplayfield = new Ext.form.DisplayField({
				value:'Database Info...'
			});
			this.timerdisplayfield = new Ext.form.DisplayField({
				value:'Timer...'
			});
			this.rowcountdisplayfield = new Ext.form.DisplayField({
				value:'Rows...'
			});
			this.items = [this.connectionstatusdisplayfield, '->',
				'-', this.hoststatusdisplayfield,
				'-', this.userstatusdisplayfield,
				'-', this.databasestatusdisplayfield,
				'-', this.timerdisplayfield,
				'-', this.rowcountdisplayfield
			];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();