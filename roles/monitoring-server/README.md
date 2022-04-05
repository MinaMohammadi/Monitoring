# Monitoring

Here we work with 3 sections [Prometheus](https://prometheus.io/docs/prometheus/latest/getting_started/), [Alertmanager](https://prometheus.io/docs/alerting/latest/overview/) and [Grafana](https://grafana.com/docs/grafana/latest/introduction/) .

Prometheus collects metrics from targets by scraping metrics HTTP endpoints. Since Prometheus exposes data in the same manner about itself, it can also scrape and monitor its own health.

The Alertmanager handles alerts sent by client applications such as the Prometheus server.

Grafana is a complete observability stack that allows you to monitor and analyze metrics, logs and traces also grafana supports querying Prometheus.

In this part of the project, we have to monitor all the servers and collect a series of criteria from these servers . Here we start by downloading the Prometheus server but we need to know that Prometheus alone is not enough because it is Prometheus pull based and requires a series of applications or libraries and servers that expose metrics To pull these metrics so we used [exporters](https://prometheus.io/docs/instrumenting/exporters/) and then Grafana for a more beautiful display .

The most famous exporter here is called the [node-exporter](https://prometheus.io/docs/guides/node-exporter/) Which is installed on all our servers. The Prometheus Node Exporter exposes a wide variety of hardware- and kernel-related metrics.

This server does not have a valid IP and can only access the Internet through a gateway.

ok let's started :)

## Prometheus

In the first step, you need to download Prometheus with any version you want to download on your server. Below is an example that you can use.

```
wget https://github.com/prometheus/prometheus/releases/download/v2.34.0/prometheus-2.34.0.linux-amd64.tar.gz
tar xvf prometheus-2.34.0.linux-amd64.tar.gz
sudo cp -r prometheus-2.34.0.linux-amd64/ /usr/local/bin/prometheus
```

Just enter the following command for your Prometheus to listen on port 9090.

```
/usr/local/bin/prometheus/prometheus
```

But if you want a cleaner way and Prometheus is running in any situation, you can define it as a service.

```
[Unit]
Description=Prometheus Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/prometheus/prometheus \
          --config.file=/usr/local/bin/prometheus/prometheus.yml

[Install]
WantedBy=multi-user.target
```

Now that Prometheus is installed, we want to install node-exporter on all servers so that Prometheus can collect metrics. 

## node-exporter

Use the following commands to install node-exporter (note that you can download the version you want).

```
wget https://github.com/prometheus/node_exporter/releases/download/v1.3.1/node_exporter-1.3.1.linux-amd64.tar.gz
tar xvf node_exporter-1.3.1.linux-amd64.tar.gz
sudo cp node_exporter-1.3.1.linux-amd64/node_exporter /usr/local/bin/
```

Just enter the following command for your Node-Exporter to listen on port 9100.

```
/usr/local/bin/node_exporter
```

But if you want a cleaner way and node-exporter is running in any situation, you can define it as a service.

```
[Unit]
Description=Node Exporter Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/node_exporter

[Install]
WantedBy=multi-user.target
```

Some of these metrics we have collected are of particular importance and can help us better monitor servers in times of properties.
We send these special metrics to Alertmanager at specific times to be managed.

## Alertmanager

The main steps to setting up alerting and notifications are :

* Setup and configure the Alertmanager
* Configure Prometheus to talk to the Alertmanager
* Create alerting rules in Prometheus

### step 1

For the first step, we need to install Alertmanager

```
wget https://github.com/prometheus/alertmanager/releases/download/v0.24.0-rc.0/alertmanager-0.24.0-rc.0.linux-amd64.tar.gz
tar xvf alertmanager-0.24.0-rc.0.linux-amd64.tar.gz
sudo cp -r alertmanager-0.24.0-rc.0.linux-amd64/ /usr/local/bin/alertmanager
```
Just enter the following command for your Alertmanager to listen on port 9093.

```
/usr/local/bin/alertmanager/alertmanager \
  --config.file=/usr/local/bin/alertmanager/alertmanager.yml \
  --cluster.advertise-address="192.168.0.2:9093"
```

You can also follow the instructions below to write the service 

```
[Unit]
Description=Alertmanager
Wants=network-online.target
After=network-online.target

[Service]
Type=simple
ExecStart=/usr/local/bin/alertmanager/alertmanager \
  --config.file=/usr/local/bin/alertmanager/alertmanager.yml \
  --cluster.advertise-address="192.168.0.2:9093"

[Install]
WantedBy=multi-user.target
```

Use the following rule to have your alerts sent to your email. Also make sure smtp is open in your Google settings.
We suggest you use `app password` to give a password to be a more secure solution for you :)

```
receivers:
  - name: 'email-notifications'
    email_configs:
    - to: "{{ email }}"
      from: "{{ email }}"
      smarthost: smtp.gmail.com:587
      auth_username: "{{ email }}"
      auth_identity: "{{ email }}"
      auth_password: "{{ password }}"
```

### step 2

In order for Prometheus to be able to send alerts to Alertmanager, enter the IP and port where Alertmanager is listening.

```
alerting:
  alertmanagers:
    - static_configs:
        - targets: ["localhost:9093"]
```

So far, we've been directing alerts through Prometheus to Alertmanager to be managed there. 

* But which alerts? 
* Where do we find these alerts?
* How do we detect a alert?

### step 3

# Monitoring

Here we work with 3 sections [Prometheus](https://prometheus.io/docs/prometheus/latest/getting_started/), [Alertmanager](https://prometheus.io/docs/alerting/latest/overview/) and [Grafana](https://grafana.com/docs/grafana/latest/introduction/) .

Prometheus collects metrics from targets by scraping metrics HTTP endpoints. Since Prometheus exposes data in the same manner about itself, it can also scrape and monitor its own health.

The Alertmanager handles alerts sent by client applications such as the Prometheus server.

Grafana is a complete observability stack that allows you to monitor and analyze metrics, logs and traces also grafana supports querying Prometheus.

In this part of the project, we have to monitor all the servers and collect a series of criteria from these servers . Here we start by downloading the Prometheus server but we need to know that Prometheus alone is not enough because it is Prometheus pull based and requires a series of applications or libraries and servers that expose metrics To pull these metrics so we used [exporters](https://prometheus.io/docs/instrumenting/exporters/) and then Grafana for a more beautiful display .

The most famous exporter here is called the [node-exporter](https://prometheus.io/docs/guides/node-exporter/) Which is installed on all our servers. The Prometheus Node Exporter exposes a wide variety of hardware- and kernel-related metrics.

This server does not have a valid IP and can only access the Internet through a gateway.

ok let's started :)

## Prometheus

In the first step, you need to download Prometheus with any version you want to download on your server. Below is an example that you can use.

```
wget https://github.com/prometheus/prometheus/releases/download/v2.34.0/prometheus-2.34.0.linux-amd64.tar.gz
tar xvf prometheus-2.34.0.linux-amd64.tar.gz
sudo cp -r prometheus-2.34.0.linux-amd64/ /usr/local/bin/prometheus
```

Just enter the following command for your Prometheus to listen on port 9090.

```
/usr/local/bin/prometheus/prometheus
```

But if you want a cleaner way and Prometheus is running in any situation, you can define it as a service.

```
[Unit]
Description=Prometheus Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/prometheus/prometheus \
          --config.file=/usr/local/bin/prometheus/prometheus.yml

[Install]
WantedBy=multi-user.target
```

Now that Prometheus is installed, we want to install node-exporter on all servers so that Prometheus can collect metrics. 

## node-exporter

Use the following commands to install node-exporter (note that you can download the version you want).

```
wget https://github.com/prometheus/node_exporter/releases/download/v1.3.1/node_exporter-1.3.1.linux-amd64.tar.gz
tar xvf node_exporter-1.3.1.linux-amd64.tar.gz
sudo cp node_exporter-1.3.1.linux-amd64/node_exporter /usr/local/bin/
```

Just enter the following command for your Node-Exporter to listen on port 9100.

```
/usr/local/bin/node_exporter
```

But if you want a cleaner way and node-exporter is running in any situation, you can define it as a service.

```
[Unit]
Description=Node Exporter Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/node_exporter

[Install]
WantedBy=multi-user.target
```

Some of these metrics we have collected are of particular importance and can help us better monitor servers in times of properties.
We send these special metrics to Alertmanager at specific times to be managed.

## Alertmanager

The main steps to setting up alerting and notifications are :

* Setup and configure the Alertmanager
* Configure Prometheus to talk to the Alertmanager
* Create alerting rules in Prometheus

### step 1

For the first step, we need to install Alertmanager

```
wget https://github.com/prometheus/alertmanager/releases/download/v0.24.0-rc.0/alertmanager-0.24.0-rc.0.linux-amd64.tar.gz
tar xvf alertmanager-0.24.0-rc.0.linux-amd64.tar.gz
sudo cp -r alertmanager-0.24.0-rc.0.linux-amd64/ /usr/local/bin/alertmanager
```
Just enter the following command for your Alertmanager to listen on port 9093.

```
/usr/local/bin/alertmanager/alertmanager \
  --config.file=/usr/local/bin/alertmanager/alertmanager.yml \
  --cluster.advertise-address="192.168.0.2:9093"
```

You can also follow the instructions below to write the service 

```
[Unit]
Description=Alertmanager
Wants=network-online.target
After=network-online.target

[Service]
Type=simple
ExecStart=/usr/local/bin/alertmanager/alertmanager \
  --config.file=/usr/local/bin/alertmanager/alertmanager.yml \
  --cluster.advertise-address="192.168.0.2:9093"

[Install]
WantedBy=multi-user.target
```

Use the following rule to have your alerts sent to your email. Also make sure smtp is open in your Google settings.
We suggest you use `app password` to give a password to be a more secure solution for you :)

```
receivers:
  - name: 'email-notifications'
    email_configs:
    - to: "{{ email }}"
      from: "{{ email }}"
      smarthost: smtp.gmail.com:587
      auth_username: "{{ email }}"
      auth_identity: "{{ email }}"
      auth_password: "{{ password }}"
```

### step 2

In order for Prometheus to be able to send alerts to Alertmanager, enter the IP and port where Alertmanager is listening.

```
alerting:
  alertmanagers:
    - static_configs:
        - targets: ["localhost:9093"]
```

So far, we've been directing alerts through Prometheus to Alertmanager to be managed there. 

* But which alerts? 
* Where do we find these alerts?
* How do we detect a alert?

### step 3

In this step, we will review the metrics we have collected and define the rules for some of them and call them in the following way in the Prometheus  config file so that Prometheus can recognize them and point them to Alertmanager .

```
rule_files:
    - "your_alerts.yml"
```
To see examples of alerts, you can look at the files directory here .

## Grafana

Now that Prometheus is collecting metrics, we can make them more beautiful and readable by using grafana and making the dashboard. There are two ways to build a dashboard :
* Build the dashboard from a special ID .
* Build a dashboard using prometheus query language.

Follow the instructions below to install Grafana 

```
wget -q -O - https://packages.grafana.com/gpg.key | sudo apt-key add -
sudo add-apt-repository "deb https://packages.grafana.com/oss/deb stable main"
sudo apt install grafana
sudo systemctl enable grafana-server
sudo systemctl start grafana-server
```

Down :)

Use the following command to see the status of the service

```
sudo systemctl status grafana-server
```

Now make your own dashboards and enjoy it :)))

Some of the IDs used in this project :

* node-exporter -> 1860
* nginx-exporter -> 12708
* mysql-exporter -> 14057
* elasticsearch-exporter -> 14191

## nginx-exporter

In addition to receiving hardware metrics, we need to receive a series of metrics about the nginx service So we follow the installation steps below

```
mkdir nginx-prometheus-exporter_0.10.0_linux_amd64
cd nginx-prometheus-exporter_0.10.0_linux_amd64
wget https://github.com/nginxinc/nginx-prometheus-exporter/releases/download/v0.10.0/nginx-prometheus-exporter_0.10.0_linux_amd64.tar.gz
tar xvf nginx-prometheus-exporter_0.10.0_linux_amd64.tar.gz
```

But note that your NGINX is listening on port 80, and here we need port 8080 to be listened to by NGINX as well . Then place the following file in path `/etc/nginx/site-enabled` . 
* If you are using IPTables, make sure that port 8080 is also open in port 80. 

```
server {
    listen 8080;
    listen [::]:8080 ;

    root /var/www/html;

    index index.html;

    location /stub_status {
      stub_status on;
    }
}
```

We will create a service for this exporter as well

```
[Unit]
Description=Prometheus Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/nginx-prometheus-exporter

[Install]
WantedBy=multi-user.target
```
We know that this service only runs on nginx server to expose nginx metrics. Also, this service is listening on port 9113.

## mysql-exporter

In order for MySQL server metrics to be exposed, we need to do the following commands

```
wget https://github.com/prometheus/mysqld_exporter/releases/download/v0.14.0/mysqld_exporter-0.14.0.linux-amd64.tar.gz
tar xvf mysqld_exporter-0.14.0.linux-amd64.tar.gz
rm mysqld_exporter-0.14.0.linux-amd64.tar.gz
sudo cp mysqld_exporter-0.14.0.linux-amd64/mysqld_exporter /usr/local/bin/
```
The most important thing in a database is the username and password we login with because this user shows how much access we have to the database . To run this exporter, we need a file that specifies this username and password As a result, we create the `.my.cnf` file in the path of the `/root/.my.cnf` .

```
[client]
user=arvan
password=arvan
```
Also, like all the steps before, we use the following commands to build the service .

```
[Unit]
Description=Mysql Exporter Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/mysqld_exporter --config.my-cnf /root/.my.cnf

[Install]
WantedBy=multi-user.target
```

We know that this service only runs on mysql server to expose mysql metrics. Also, this service is listening on port 9104.

## elasticsearch-exporter

This is the last station :)

Use the following commands and install elasticsearch-exporter on your elasticsearch servers . 

```
wget https://github.com/prometheus-community/elasticsearch_exporter/releases/download/v1.3.0/elasticsearch_exporter-1.3.0.linux-amd64.tar.gz
tar xvf elasticsearch_exporter-1.3.0.linux-amd64.tar.gz
rm elasticsearch_exporter-1.3.0.linux-amd64.tar.gz
sudo cp -r elasticsearch_exporter-1.3.0.linux-amd64/ /usr/local/bin/elasticsearch
```

As with all previous steps, write the existing file service again

```
[Unit]
Description=Prometheus Service
After=network.target

[Service]
Type=simple
ExecStart=/usr/local/bin/elasticsearch/elasticsearch_exporter

[Install]
WantedBy=multi-user.target
```

We know that this service only runs on elasticsearch server to expose elasticsearch metrics. Also, this service is listening on port 9114.

## License

In this document, we understood the basic concept of monitoring and learned how to work with the tools that were introduced . For a better understanding, we recommend that you look at the 3 links that we have introduced to get a better and faster understanding of this content .

* https://www.youtube.com/watch?v=7gW5pSM6dlU
* https://www.youtube.com/watch?v=x2qTvTN8YKI
* https://www.youtube.com/watch?v=zTZe447nDhI
* https://www.youtube.com/watch?v=YUabB_7H710&t=187s
