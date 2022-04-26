<!-- @format -->

<template>
  <div id="page-body">
    <div id="page-content">
      <div class="panel">
        <!-- <div class="panel-heading">
        
        </div> -->
        <div class="panel-body">
          <div class="panel-heading pad-all ">
            <div class="panel-control">
              <button
                class="demo-panel-ref-btn btn btn-default"
                data-toggle="panel-overlay"
                data-target="#demo-panel-collapse-default"
                id="demo-state-btn"
                @click="refresh($event)"
                data-loading-text="Loading..."
              >
                <i class="demo-psi-repeat-2 icon-fw"></i> Refresh Table
              </button>
            </div>
            <h3
              class="text-thin bord-btm "
              style="font-size: 20px;padding: 10px;"
            >
              <i class="demo-pli-map-2 icon-lg"></i> Rack Area Monitoring
            </h3>
          </div>
          <div class="row">
            <div
              class="table-responsive panel-body"
              id="demo-panel-collapse-default"
            >
              <div class="row pad-top">
                <div class="col-md-6 table-toolbar-left form-horizontal">
                  <div class="row pad-all" style="padding-left: 10px;">
                    <label class="col-lg-3 control-label text-thin">
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
                <div class="col-md-6 table-toolbar-right form-horizontal">
                  <div class="row pad-all">
                    <router-link
                      to="/location"
                      class="btn btn-info btn-rounded mar-lft"
                    >
                      <i class="fa fa-arrow-circle-left icon-lg"></i> Location
                      Setup
                    </router-link>
                  </div>
                </div>
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
                      <th class="text-center text-main">Rack(s)</th>
                      <th class="text-center text-main">Status</th>
                    </tr>
                  </thead>
                  <tbody v-if="!data.data.length">
                    <tr>
                      <td
                        colspan="6"
                        style="text-align: center;"
                        class="mar-top"
                      >
                        No data available.
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-for="(data, index) in data.data" :key="index">
                    <tr>
                      <td :rowspan="data.racks.length + 1" class="text-thin">
                        {{ data.company }}
                      </td>
                      <td :rowspan="data.racks.length + 1" class="text-thin">
                        {{ data.business_unit }}
                      </td>
                      <td :rowspan="data.racks.length + 1" class="text-thin">
                        {{ data.department }}
                      </td>
                      <td :rowspan="data.racks.length + 1" class="text-thin">
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
                    <tr v-for="(racks, i) in data.racks" :key="i">
                      <td class="text-thin">
                        {{ racks.rack_desc }}
                      </td>

                      <td class="text-center">
                        <span
                          class="badge badge-icon badge-fw"
                          :class="{
                            'badge-success': racks.rackGroup.every(
                              rack => rack.done == 'true'
                            ),
                            'badge-warning': !racks.rackGroup.every(
                              rack => rack.done == 'true'
                            )
                          }"
                        ></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-6">
                      <small>Showing {{ data.data.length }} result(s)</small>
                    </div>
                    <div class="col-md-6"></div>
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
    },
    refresh(e) {
      const thisButton = e.target
      const oldHTML = thisButton.innerHTML
      thisButton.disabled = true
      thisButton.innerHTML =
        '<i class="fa fa-spinner fa-pulse fa-fw"></i> Loading...'

      setTimeout(() => {
        this.getResults()
        thisButton.disabled = false
        thisButton.innerHTML = oldHTML
      }, 800)
    }
  },
  mounted() {
    $('.demo-panel-ref-btn')
      .niftyOverlay({
        iconClass: 'demo-psi-repeat-2 spin-anim icon-2x'
      })
      .on('click', function() {
        var $el = $(this),
          relTime
        $el.niftyOverlay('show')

        // Do something...

        relTime = setInterval(function() {
          // Hide the screen overlay
          $el.niftyOverlay('hide')

          clearInterval(relTime)
        }, 800)
      })

    this.$root.currentPage = 'this.$route.meta.name'
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
