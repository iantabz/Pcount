/** @format */

import Vue from 'vue'
import VueRouter from 'vue-router'

import Dashboard from './components/Dashboard.vue'
import PhysicalCount from './components/CountData.vue'
import Location from './components/Location.vue'
import Users from './components/Users.vue'
import NavUpload from './components/NavUpload.vue'
import ConsolidateNav from './components/ConsolidationFromNav.vue'
import VarianceReportCost from './components/VarianceReportCost.vue'
import VarianceReport from './components/VarianceReport.vue'
import ConsolidateReport from './components/ConsolidateReport.vue'
import NotFound from './components/NotFound.vue'
import VendorMasterfile from './components/VendorMasterfile.vue'
import Category from './components/Category.vue'
import PhysicalCountCost from './components/PhysicalCountCost.vue'
import PosUnposted from './components/PosUnposted.vue'
import DamageCount from './components/DamageCount.vue'
import LocationOrig from './components/Location2.vue'
import RackMonitoring from './components/RackMonitoring.vue'
import InventoryValuationVariance from './components/InventoryValuationVariance.vue'
import NavSys from './components/NavSys.vue'
import NotInCount from './components/NotInCount.vue'
import BackendSetup from './components/SetupByBackend.vue'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  routes: [
    // {
    //   name: '',
    //   path: '',
    //   component: ,
    //   meta: {
    //     name: ''
    //   }
    // }
    {
      name: 'Dashboard',
      path: '/dashboard',
      component: Dashboard,
      meta: {
        name: 'Dashboard'
      }
    },
    {
      name: 'Actual Count (APP)',
      path: '/physical_count',
      component: PhysicalCount,
      meta: {
        name: 'Actual Count (APP)'
      }
    },
    {
      name: 'Actual Count (APP) for Damages',
      path: '/damage_count',
      component: DamageCount,
      meta: {
        name: 'Actual Count (APP) for Damages'
      }
    },
    {
      name: 'Location Setup (App)',
      path: '/location',
      component: Location,
      meta: {
        name: 'Location Setup (App)'
      }
    },
    {
      name: 'Location Setup (App)',
      path: '/location2',
      component: LocationOrig,
      meta: {
        name: 'Location Setup (App)'
      }
    },
    {
      name: 'User Setup',
      path: '/users',
      component: Users,
      meta: {
        name: 'User Setup'
      }
    },
    {
      name: 'Vendor Masterfile',
      path: '/vendor_masterfile',
      component: VendorMasterfile,
      meta: {
        name: 'Vendor Masterfile'
      }
    },
    {
      name: 'Item Department',
      path: '/category',
      component: Category,
      meta: {
        name: 'Item Department'
      }
    },
    {
      name: 'Upload Inventory Valuation from Nav',
      path: '/nav_upload',
      component: NavUpload,
      meta: {
        name: 'Upload Inventory Valuation from Nav'
      }
    },
    {
      name: 'POS Unposted',
      path: '/pos_unposted',
      component: PosUnposted,
      meta: {
        name: 'POS Unposted'
      }
    },
    {
      name: 'Consolidate Report w/ Cost',
      path: '/consolidate_report_cost',
      component: ConsolidateNav,
      meta: {
        name: 'Consolidate Report w/ Cost'
      }
    },
    {
      name: 'Consolidated Variance Report with Cost',
      path: '/variance_report_cost',
      component: VarianceReportCost,
      meta: {
        name: 'Consolidated Variance Report with Cost'
      }
    },
    {
      name: 'Consolidated Variance Report',
      path: '/variance_report',
      component: VarianceReport,
      meta: {
        name: 'Consolidated Variance Report'
      }
    },
    {
      name: 'Consolidated Report',
      path: '/consolidate_report',
      component: ConsolidateReport,
      meta: {
        name: 'Consolidated Report'
      }
    },
    {
      name: 'Inventory Valuation per Actual Count',
      path: '/inventory_valuation',
      component: PhysicalCountCost,
      meta: {
        name: 'Inventory Valuation per Actual Count'
      }
    },
    {
      name: 'Rack Area Monitoring',
      path: '/rack_monitoring',
      component: RackMonitoring,
      meta: {
        name: 'Rack Area Monitoring'
      }
    },
    {
      name: 'Inventory Valuation From Navision w/ Variances',
      path: '/inventory_valuation_variance',
      component: InventoryValuationVariance,
      meta: {
        name: 'Inventory Valuation From Navision w/ Variances'
      }
    },
    {
      name: 'Inventory Balance per Navision',
      path: '/inventory_balance',
      component: NavSys,
      meta: {
        name: 'Inventory Balance per Navision'
      }
    },
    {
      name: 'Negative Variance Report',
      path: '/not_in_count',
      component: NotInCount,
      meta: {
        name: 'Negative Variance Report'
      }
    },
    {
      name: 'Count Setup by Backend',
      path: '/backend_setup',
      component: BackendSetup,
      meta: {
        name: 'Count Setup by Backend'
      }
    },
    {
      path: '*',
      name: 'notFound',
      component: NotFound,
      meta: {
        name: 'Page not found'
      }
    }
  ]
})

export default router
