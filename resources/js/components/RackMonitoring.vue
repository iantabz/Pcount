<!-- @format -->

<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <div class="panel-body">
          <div class="panel-heading pad-all">
            <h3
              class="panel-heading bord-btm text-thin"
              style="font-size: 20px;/* padding: 15px 0px 0px 25px; */"
            >
              <i class="demo-pli-map-2 icon-lg"></i> {{ $root.currentPage }}
            </h3>
          </div>
          <div class="row">
            <div class="table-responsive panel-body">
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <div class="row pad-all" style="padding-left: 10px;">
                    <label class="col-lg-3 control-label text-bold">
                      <h5>
                        <i class="icon-lg demo-pli-calendar-4 icon-fw"></i>
                        Count Date :
                      </h5>
                    </label>
                    <div class="col-lg-6">
                      <input
                        class="form-control"
                        v-model="date"
                        type="date"
                        name="dateFrom"
                        id="dateFrom"
                        style="border-radius: 4px"
                        min="dateToday"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 table-toolbar-right form-horizontal"></div>
                <table
                  class="table table-condensed table-vcenter table-hover"
                  id="datatable"
                >
                  <thead>
                    <tr>
                      <th class="text-center text-main">Company</th>
                      <th class="text-center text-main">Business Unit</th>
                      <th class="text-center text-main">Department</th>
                      <th class="text-center text-main">Section</th>
                      <th class="text-center text-main">Rack</th>
                      <th class="text-center text-main">Status</th>
                    </tr>
                  </thead>
                  <tbody v-if="!data.data.length">
                    <tr>
                      <td colspan="5" style="text-align: center;">
                        No data available.
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-for="(data, index) in data.data" :key="index">
                    <tr>
                      <td :rowspan="data.racks.length + 1">
                        {{ data.company }}
                      </td>
                      <td :rowspan="data.racks.length + 1">
                        {{ data.business_unit }}
                      </td>
                      <td :rowspan="data.racks.length + 1">
                        {{ data.department }}
                      </td>
                      <td :rowspan="data.racks.length + 1">
                        {{ data.section }}
                      </td>
                      <!-- <td>
                        <div
                          class="label label-success mar-all"
                          v-for="(r, i) in data.racks"
                          :key="i"
                        >
                          {{ r.rack_desc }} <br />
                        </div>
                      </td> -->
                    </tr>
                    <tr v-for="(r, i) in data.racks" :key="i">
                      <td>{{ r.rack_desc }}</td>
                      <td v-if="r.done == 'true'" class="text-center">
                        <span class="label label-success">Done</span>
                      </td>
                      <td v-else class="text-center">
                        <span class="label label-warning">On going</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
      date: this.getFormattedDateToday(),
      dateToday: this.getFormattedDateToday(),
      total_result: null
    }
  },

  watch: {
    date() {
      this.getResults()
    }
  },
  methods: {
    getFormattedDateToday() {
      return new Date()
        .toJSON()
        .slice(0, 10)
        .replace(/-/g, '-')
    },
    getResults(page = 1) {
      console.log('getting data...')
      let url = null,
        type = 'LocationMonitoring'

      axios
        .get(
          `/setup/location/getResults/?date=${btoa(
            this.date
          )}&type=${type}&company=${this.company}&bu=${
            this.business_unit
          }&dept=${this.department}&section=${this.section}&page=`
        )
        .then(response => {
          this.data = response.data
          this.total_result = response.data.total
        })
    }
  },
  mounted() {
    this.$root.currentPage = this.$route.meta.name
    this.getResults()
    document.getElementById('dateFrom').setAttribute('min', this.dateToday)
  }
}
</script>

<style scoped>
#container .table td {
  font-size: 1.1em;
}

#container .table-hover > tbody > tr:hover {
  background-color: rgb(2 2 2 / 5%);
}
</style>
