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
      name: 'Upload Inventory Balance from Nav',
      path: '/nav_upload',
      component: NavUpload,
      meta: {
        name: 'Upload Inventory Balance from Nav'
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
      name: 'Variance Report with Cost',
      path: '/variance_report_cost',
      component: VarianceReportCost,
      meta: {
        name: 'Variance Report with Cost'
      }
    },
    {
      name: 'Variance Report',
      path: '/variance_report',
      component: VarianceReport,
      meta: {
        name: 'Variance Report'
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
      name: 'Actual Count (APP) with Cost',
      path: '/physical_count_cost',
      component: PhysicalCountCost,
      meta: {
        name: 'Actual Count (APP) with Cost'
      }
    },
    {
      name: 'Rack Monitoring',
      path: '/rack_monitoring',
      component: RackMonitoring,
      meta: {
        name: 'Rack Monitoring'
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
