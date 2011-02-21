Ext.ns('monoql.bar');
monoql.bar.maintoolbar = function() {
	var cls = 'monoql-bar-maintoolbar';
	var Class = Ext.extend(monoql.bar.toolbar, {
		height:26,
		initComponent: function() {
			this.filemenu = new monoql.menu.filemenu();
			this.editmenu = new monoql.menu.editmenu();
			this.toolsmenu = new monoql.menu.toolsmenu();
			this.datamenu = new monoql.menu.datamenu();
			this.helpmenu = new monoql.menu.helpmenu();
			this.openfilebutton = new monoql.button.openfilebutton();
			this.savefilebutton = new monoql.button.savefilebutton();
			this.runquerybutton = new monoql.button.runquerybutton();
			this.cancelquerybutton = new monoql.button.cancelquerybutton();
			this.addquerytabbutton = new monoql.button.addquerytabbutton();
			this.resultstogridbutton = new monoql.button.resultstogridbutton();
			this.resultstotextbutton = new monoql.button.resultstotextbutton();
			this.resultstofilebutton = new monoql.button.resultstofilebutton();
			this.connectioncombobox = new monoql.form.connectioncombobox({
				disabled:true
			});
			this.databasecombobox = new monoql.form.databasecombobox({
				disabled:true
			});
			this.items = [{
				text:'File',
				menu:this.filemenu
			}, {
				text:'Edit',
				menu:this.editmenu
			}, {
				text:'Tools',
				menu:this.toolsmenu
			}, {
				text:'Data',
				menu:this.datamenu
			}, {
				text:'Help',
				menu:this.helpmenu
			},
				'-', 
				this.openfilebutton, 
				this.savefilebutton,
				'-', 
				this.runquerybutton, 
				this.cancelquerybutton,
				this.addquerytabbutton,
				'-', 
				this.resultstogridbutton, 
				this.resultstotextbutton, 
				this.resultstofilebutton,
				'-',
				this.connectioncombobox,
				'-',
				this.databasecombobox
			];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		initListeners:function() {
			ui.tabs.on({
				scope:this,
				tabchange:this.onMainTabSetTabChange,
				add:this.onMainTabSetAdd
			});
			
		},
		onMainTabSetAdd:function(tabset, tab, index) {
			if (tab.getXType()==='monoql-tab-querytab') {
				tab.queryform.on({
					scope:this,
					beforequery:this.onQueryTabBeforeQuery,
					query:this.onQueryTabQuery,
					queryresult:this.onQueryTabQueryResult,
					cancelquery:this.onQueryTabCancelQuery
				});
			}
		},
		onQueryTabBeforeQuery:function(queryform, query, connection) {
		},
		onQueryTabQuery:function(queryform, query, connection) {
			this.runquerybutton.disable();
			this.cancelquerybutton.enable();
		},
		onQueryTabQueryResult:function(queryform, query, connection, result) {
			this.cancelquerybutton.disable();
			this.runquerybutton.enable();
		},
		onQueryTabCancelQuery:function(queryform, connection) {
			this.cancelquerybutton.disable();
			this.runquerybutton.enable();
		},
		onMainTabSetTabChange:function(tabset, tab) {
			(!tabset.getActiveTab() ? this.onNoActiveTab : this.onActiveTab).call(this, tab);
		},
		onNoActiveTab:function() {
			this.runquerybutton.disable();
			this.connectioncombobox.reset();
			this.connectioncombobox.setDisabled(true);
		},
		onActiveTab:function(tab) {
			if (tab.getXType()==='monoql-tab-querytab' && !tab.executing) {
				this.runquerybutton.enable();
			}
			if (tab.connection) {
				this.connectioncombobox.setValue(tab.connection.get('id'));
				this.connectioncombobox.setDisabled(false);
			}
		}
		
	});
	Ext.reg(cls, Class);
	return Class;
}();