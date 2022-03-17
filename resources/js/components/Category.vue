<!-- @format -->

<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <h3
              class="panel-heading text-thin"
              style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
            >
              <i class="demo-pli-data-settings icon-lg"></i>
              {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row">
                <div class="col-lg-6 table-toolbar-left form-horizontal"></div>
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <div class="row" style="padding: 10px 15px 15px 10px">
                    <label
                      class="col-md-6 control-label"
                      style="text-align: right"
                    >
                      <h5>Search :</h5></label
                    >
                    <div class="col-md-6">
                      <v-select
                        v-model="category"
                        @search="retrieveCategory"
                        label="category"
                        :options="categoryList"
                        placeholder="Search Item Department"
                        :filterable="false"
                      >
                      </v-select>
                    </div>
                  </div>
                </div>
              </div>
              <table class="table table-striped table-vcenter table-hover">
                <thead>
                  <tr>
                    <th>Item Department</th>
                    <th>Item Department Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="!data.data.length">
                    <td colspan="2" style="text-align: center;">
                      No data available.
                    </td>
                  </tr>
                  <tr v-for="(data, index) in data.data" :key="index">
                    <td>{{ data.category }}</td>
                    <td>{{ data.dept_code }}</td>
                  </tr>
                </tbody>
              </table>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-6">
                    Showing {{ data.from }} to {{ data.to }} of
                    {{ data.total }} entries
                  </div>
                  <div class="col-md-6">
                    <div class="text-right">
                      <pagination
                        style="margin: 0 0 20px 0"
                        :limit="1"
                        :show-disabled="true"
                        :data="data"
                        @pagination-change-page="getResults"
                      ></pagination>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { debounce } from 'lodash'

Vue.component('pagination', require('laravel-vue-pagination'))
Vue.component('v-select', vSelect)
export default {
  data() {
    return {
      data: {
        data: [],
        current_page: null,
        from: null,
        to: null,
        total: null,
        per_page: null
      },
      total_result: null,
      categoryList: [],
      category: null
    }
  },
  watch: {
    category(val) {
      if (!val) {
        this.getResults()
        this.categoryList = []
      }
    }
  },
  methods: {
    retrieveCategory(search, loading) {
      loading(true)
      this.search(search, loading, this)
    },
    search: debounce((search, loading, vm) => {
      if (search.trim().length > 0) {
        axios
          .get(`/uploading/nav_upload/getCategory?category=${search}`)
          .then(({ data }) => {
            vm.categoryList = data
            vm.data.data = data
            vm.data.to = 1
            vm.data.total = 1
            loading(false)
          })
          .catch(error => {
            vm.categoryList = []
            loading(false)
          })
      } else {
        loading(false)
      }
    }, 1000),
    getResults(page = 1) {
      let url = `/setup/masterfiles/getItemCategoryMasterfile/?page=`

      axios.get(url + page).then(({ data }) => {
        this.data = data
      })
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.getResults()
  }
}
</script>

<style scoped>
#container .table th {
  font-size: 1.05em;
  font-weight: 600;
  border-bottom: 3px solid rgba(0, 0, 0, 0.07);
  border-top: 1px solid rgba(0, 0, 0, 0.07);
  border-left: 1px solid rgba(0, 0, 0, 0.07);
  border-right: 1px solid rgba(0, 0, 0, 0.07);
  color: #4d627b;
}

#container .table > tbody > tr > td {
  padding: 8px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.07);
  border-top: 1px solid rgba(0, 0, 0, 0.07);
  border-left: 1px solid rgba(0, 0, 0, 0.07);
  border-right: 1px solid rgba(0, 0, 0, 0.07);
}

#container .table-bordered > tbody > tr:nth-child(2n + 1) {
  background-color: rgba(0, 0, 0, 0.05);
}

#container .table-hover > tbody > tr:hover {
  background-color: rgba(0, 0, 0, 0.035);
}
</style>
