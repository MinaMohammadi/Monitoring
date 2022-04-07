# What is Elasticsearch?
Elasticsearch is a distributed, free and open search and analytics engine for all types of data, including textual, numerical, geospatial, structured, and unstructured. Elasticsearch is built on Apache Lucene and was first released in 2010 by Elasticsearch N.V. (now known as Elastic). 

Known for its simple REST APIs, distributed nature, speed, and scalability, Elasticsearch is the central component of the Elastic Stack, a set of free and open tools for data ingestion, enrichment, storage, analysis, and visualization. Commonly referred to as the ELK Stack (after Elasticsearch, Logstash, and Kibana), the Elastic Stack now includes a rich collection of lightweight shipping agents known as Beats for sending data to Elasticsearch. 


## Introduction
The Elastic Stack has four main components:

- Elasticsearch: a distributed RESTful search engine which stores all of the collected data.
- Logstash: the data processing component of the Elastic Stack which sends incoming data to Elasticsearch.
- Kibana: a web interface for searching and visualizing logs.
- Beats: lightweight, single-purpose data shippers that can send data from hundreds or thousands of machines to either Logstash or Elasticsearch.

## Prerequisites
To complete this tutorial, you will need the following:

- An Ubuntu 20.04 server with 4GB RAM and 2 CPUs set up with a non-root sudo user.
- OpenJDK 11 installed. See the section [Installing the Default JRE/JDK].
- Nginx installed on your server, which we will configure later in this guide as a reverse proxy for Kibana.


***

# Step 0) Installing the Default JRE/JDK
The easiest option for installing Java is to use the version packaged with Ubuntu. By default, Ubuntu 20.04 includes Open JDK 11, which is an open-source variant of the JRE and JDK.

Follow the steps below to install Java OpenJDK 11 on your Ubuntu system:

01) First, update the apt package index with:
`sudo apt update`

02) Once the package index is updated install the default Java OpenJDK package with:
`sudo apt install openjdk-11-jdk`

03) Verify the installation, by running the following command which will print the Java version:
`java -version `

The output will look something like this:
```
output

openjdk version "11.0.2" 2019-01-15
OpenJDK Runtime Environment (build 11.0.2+9-Ubuntu-3ubuntu118.04.3)
OpenJDK 64-Bit Server VM (build 11.0.2+9-Ubuntu-3ubuntu118.04.3, mixed mode, sharing)
```

***
# Step 1) Installing and Configuring Elasticsearch
The Elasticsearch components are not available in Ubuntu’s default package repositories. They can, however, be installed with APT after adding Elastic’s package source list.

All of the packages are signed with the Elasticsearch signing key in order to protect your system from package spoofing. Packages which have been authenticated using the key will be considered trusted by your package manager. In this step, you will import the Elasticsearch public GPG key and add the Elastic package source list in order to install Elasticsearch.

To begin, use cURL, the command line tool for transferring data with URLs, to import the Elasticsearch public GPG key into APT. Pipe the output of the cURL command into the apt-key program, which adds the public GPG key to APT.

```
curl -fsSL https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -

```
Next, add the Elastic source list to the sources.list.d directory, where APT will search for new sources:

```
echo "deb https://artifacts.elastic.co/packages/7.x/apt stable main" | sudo tee -a /etc/apt/sources.list.d/elastic-7.x.list
```

Next, update your package lists so APT will read the new Elastic source:

```
sudo apt update
```
Then install Elasticsearch with this command:

```
sudo apt install elasticsearch
```

to check your Elasticsearch version from the command line:

```
/usr/share/elasticsearch/bin/elasticsearch --version

```
Elasticsearch is now installed and ready to be configured. Use your preferred text editor to edit Elasticsearch’s main configuration file, elasticsearch.yml. Here, we’ll use nano:

```
sudo nano /etc/elasticsearch/elasticsearch.yml
```
The elasticsearch.yml file provides configuration options for your cluster, node, paths, memory, network, discovery, and gateway. Most of these options are preconfigured in the file but you can change them according to your needs. For the purposes of our demonstration of a single-server configuration, we will only adjust the settings for the network host.

```
# ---------------------------------- Cluster -----------------------------------#
# Use a descriptive name for your cluster:
cluster.name:  Bokhara-elasticsearch
#
# ------------------------------------ Node ------------------------------------
# Use a descriptive name for the node:
node.name: master-node
#
node.master: true
#
node.data: true
# ----------------------------------- Paths ------------------------------------
# Path to directory where to store the data (separate multiple locations by comma):
path.data: /var/lib/elasticsearch
#
# Path to log files:
path.logs: /var/log/elasticsearch
#
# ----------------------------------- Memory -----------------------------------
# Lock the memory on startup:
bootstrap.memory_lock: true
#
# ---------------------------------- Network -----------------------------------
# By default Elasticsearch is only accessible on localhost. Set a different
# address here to expose this node on the network:
network.host: 0.0.0.0
http.port: 9200
#
# --------------------------------- Discovery ----------------------------------
# Pass an initial list of hosts to perform discovery when this node is started:
# The default list of hosts is ["127.0.0.1", "[::1]"]
#
discovery.seed_hosts: ["192.168.0.230"]
#
# Bootstrap the cluster using an initial set of master-eligible nodes:
discovery.type: single-node
#
#cluster.initial_master_nodes: ["node-1", "node-2"]
#
# ---------------------------------- Security ----------------------------------
#
#                                 *** WARNING ***
#
# To protect your data, we strongly encourage you to enable the Elasticsearch security features. 
# Refer to the following documentation for instructions.

xpack.security.enabled: true
```

Start the Elasticsearch service with systemctl. Give Elasticsearch a few moments to start up. Otherwise, you may get errors about not being able to connect.

`sudo systemctl start elasticsearch`

Next, run the following command to enable Elasticsearch to start up every time your server boots:

`sudo systemctl enable elasticsearch`

You can test whether your Elasticsearch service is running by sending an HTTP request:

`curl -X GET "localhost:9200"`

You will see a response showing some basic information about your local node, similar to this:

```
Output
{
  "name" : "Elasticsearch",
  "cluster_name" : "elasticsearch",
  "cluster_uuid" : "qqhFHPigQ9e2lk-a7AvLNQ",
  "version" : {
    "number" : "7.7.1",
    "build_flavor" : "default",
    "build_type" : "deb",
    "build_hash" : "ef48eb35cf30adf4db14086e8aabd07ef6fb113f",
    "build_date" : "2020-03-26T06:34:37.794943Z",
    "build_snapshot" : false,
    "lucene_version" : "8.5.1",
    "minimum_wire_compatibility_version" : "6.8.0",
    "minimum_index_compatibility_version" : "6.0.0-beta1"
  },
  "tagline" : "You Know, for Search"
}
```

***
# Step 2) Installing and Configuring the Kibana Dashboard
According to the official documentation, you should install Kibana only after installing Elasticsearch. Installing in this order ensures that the components each product depends on are correctly in place.

Because you’ve already added the Elastic package source in the previous step, you can just install the remaining components of the Elastic Stack using apt:

`sudo apt install kibana`

Then enable and start the Kibana service:

`sudo systemctl enable kibana`

`sudo systemctl start kibana`

Because Kibana is configured to only listen on localhost, we must set up a reverse proxy to allow external access to it. We will use Nginx for this purpose, which should already be installed on your server.

## Nginx server block 

Next, we will create an Nginx server block file. As an example, we will refer to this file as your_domain, although you may find it helpful to give yours a more descriptive name. For instance, if you have a FQDN and DNS records set up for this server, you could name this file after your FQDN.

Using nano or your preferred text editor, create the Nginx server block file:

`sudo nano /etc/nginx/sites-available/your_domain`

Add the following code block into the file, being sure to update your_domain to match your server’s FQDN or public IP address. This code configures Nginx to direct your server’s HTTP traffic to the Kibana application, which is listening on localhost:5601.

```
server {
    listen 80;

    server_name your_domain;
    
    location / {
        proxy_pass http://localhost:5601;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}

```
When you’re finished, save and close the file.

Next, enable the new configuration by creating a symbolic link to the sites-enabled directory. If you already created a server block file with the same name in the Nginx prerequisite, you do not need to run this command:

`sudo ln -s /etc/nginx/sites-available/your_domain /etc/nginx/sites-enabled/your_domain`

Then check the configuration for syntax errors:

`sudo nginx -t`

If any errors are reported in your output, go back and double check that the content you placed in your configuration file was added correctly. Once you see syntax is ok in the output, go ahead and restart the Nginx service:

`sudo systemctl reload nginx`

Kibana is now accessible via your FQDN or the public IP address of your Elastic Stack server. You can check the Kibana server’s status page by navigating to the following address and entering your login credentials when prompted:

`http://your_domain/status`

# Step 3) Installing and Configuring Logstash

Although it’s possible for Beats to send data directly to the Elasticsearch database, it is common to use Logstash to process the data. This will allow you more flexibility to collect data from different sources, transform it into a common format, and export it to another database.
Install Logstash with this command:

`sudo apt install logstash`

After installing Logstash, you can move on to configuring it. Logstash’s configuration files reside in the /etc/logstash/conf.d directory.As you configure the file, it’s helpful to think of Logstash as a pipeline which takes in data at one end, processes it in one way or another, and sends it out to its destination (in this case, the destination being Elasticsearch). 

Create a configuration file called logstash.conf where you will set up your Filebeat input, filter,output:

```
input {                                                                
   beats {                                                              
       port => 5044                                                       
   }                                                                    
}

filter {
    #if "syslog" in [tags] {
      grok {

        #match => { "message" => "%{SYSLOGTIMESTAMP:syslog_timestamp} %{SYSLOGHOST:syslog_hostname} %{DATA:syslog_program}(?:\[%{POSINT:syslog_pid}\])?: %{GREEDYDATA:syslog_message}" }


        add_field => [ "received_at", "%{@timestamp}" ]
        add_field => [ "received_from", "%{host}" ]
      #}
      date {
        match => [ "log_timestamp", "MMM  d HH:mm:ss", "MMM dd HH:mm:ss" ]
      }
   }
}

output {
  if "syslog" in [tags] {
    elasticsearch {
      hosts => ["192.168.0.133:9200"]
      index => "syslog-%{+YYYY.MM.dd}"
    }
  } else {
    elasticsearch {
      hosts => ["192.168.0.133:9200"]
      index => "authlog-%{+YYYY.MM.dd}"
    }
  } 
}

```
Save and close the file.

Test your Logstash configuration with this command:

`sudo -u logstash /usr/share/logstash/bin/logstash --path.settings /etc/logstash -t`

If there are no syntax errors, your output will display Config Validation Result: OK. Exiting Logstash after a few seconds. If you don’t see this in your output, check for any errors noted in your output and update your configuration to correct them. Note that you will receive warnings from OpenJDK, but they should not cause any problems and can be ignored.

If your configuration test is successful, start and enable Logstash to put the configuration changes into effect:

`sudo systemctl start logstash`

`sudo systemctl enable logstash`

Now that Logstash is running correctly and is fully configured.

# Step 4) Installing and Configuring Filebeat

The Elastic Stack uses several lightweight data shippers called Beats to collect data from various sources and transport them to Logstash or Elasticsearch.

In this project we will use Filebeat to forward local logs to our Elastic Stack.

```
Notice!!
In order to install filebeat-agent on every client, you neet to:

1. Download and install the Public Signing Key
2. Install the apt-transport-https package on Debian
3. Save the repository definition to /etc/apt/sources.list.d/elastic-7.x.list.
```

Install Filebeat using apt:

`sudo apt install filebeat`

Next, configure Filebeat to connect to Logstash. Here, we will modify the example configuration file that comes with Filebeat.

Filebeat supports numerous outputs, but you’ll usually only send events directly to Elasticsearch or to Logstash for additional processing. In this project, we’ll use Logstash to perform additional processing on the data collected by Filebeat. Filebeat will not need to send any data directly to Elasticsearch, so let’s disable that output. 

To do so, find the output.elasticsearch section and comment out the following lines by preceding them with a #:
Open the Filebeat configuration file.

```
...
#output.elasticsearch:
  # Array of hosts to connect to.
  #hosts: ["localhost:9200"]
...

```
Save and close the file.

The functionality of Filebeat can be extended with Filebeat modules. In this tutorial we will use the system module, which collects and parses logs created by the system logging service of common Linux distributions.

Let’s enable it:

`sudo filebeat modules enable system`

By default, Filebeat is configured to use default paths for the syslog and authorization logs. In the case of this tutorial, you do not need to change anything in the configuration. You can see the parameters of the module in the /etc/filebeat/modules.d/system.yml configuration file.

Next, we need to set up the Filebeat ingest pipelines, which parse the log data before sending it through logstash to Elasticsearch. To load the ingest pipeline for the system module, enter the following command:

` sudo filebeat setup --pipelines --modules system`

Next, load the index template into Elasticsearch. An Elasticsearch index is a collection of documents that have similar characteristics. Indexes are identified with a name, which is used to refer to the index when performing various operations within it. The index template will be automatically applied when a new index is created.

To load the template, use the following command:

`sudo filebeat setup --index-management -E output.logstash.enabled=false -E 'output.elasticsearch.hosts=["localhost:9200"]' `

```
Output
Index setup finished.
```

Filebeat comes packaged with sample Kibana dashboards that allow you to visualize Filebeat data in Kibana. Before you can use the dashboards, you need to create the index pattern and load the dashboards into Kibana.

As the dashboards load, Filebeat connects to Elasticsearch to check version information. To load dashboards when Logstash is enabled, you need to disable the Logstash output and enable Elasticsearch output:

`sudo filebeat setup -E output.logstash.enabled=false -E output.elasticsearch.hosts=['localhost:9200'] -E setup.kibana.host=localhost:5601`

You should receive output similar to this:

```
Output
Overwriting ILM policy is disabled. Set `setup.ilm.overwrite:true` for enabling.

Index setup finished.
Loading dashboards (Kibana must be running and reachable)
Loaded dashboards
Setting up ML using setup --machine-learning is going to be removed in 8.0.0. Please use the ML app instead.
See more: https://www.elastic.co/guide/en/elastic-stack-overview/current/xpack-ml.html
Loaded machine learning job configurations
Loaded Ingest pipelines
```

Now you can start and enable Filebeat:

```
sudo systemctl start filebeat
sudo systemctl enable filebeat
```

If you’ve set up your Elastic Stack correctly, Filebeat will begin shipping your syslog and authorization logs to Logstash, which will then load that data into Elasticsearch.

To verify that Elasticsearch is indeed receiving this data, query the Filebeat index with this command:

`curl -XGET 'http://localhost:9200/filebeat-*/_search?pretty'`

You should receive output similar to this:

```
{
  "count" : 14254,
  "_shards" : {
    "total" : 3,
    "successful" : 3,
    "skipped" : 0,
    "failed" : 0
  }
}

```
