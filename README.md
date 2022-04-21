# Monitoring

In this project, we have tried to automate [Project Phonebook](https://github.com/Ygn-Amirjani/Phonebook) with the ansible. We have also added two [Monitoring](https://github.com/MinaMohammadi/Monitoring/tree/master/roles/monitoring-server) and [ELK](https://github.com/MinaMohammadi/Monitoring/tree/master/roles/elk) servers, the description of which you can read from the relevant links .

![Project outline](ProjectImage/photo5879718642797623379.jpg)

as we know You make your request through your laptop to address Nginx server at port 18080 . So Nginx reverse proxy your request to the Rest server . 

In the meantime, the monitoring server monitors all servers by Prometheus and the log server collects all errors/logs .

Also, these two servers do not have valid IPs and can only access the Internet via Gateway .

Here we tried to correct our previous bugs and provide you with a cleaner project .

## Raise the project

To start the project, you must first install X on your system, so :

```
sudo apt install -y ansible
```

Now run gateway_playbook.yml to add whatever you want to the gateway server .

```
ansible-playbook gateway_playbook.yml
```

Here is the last step, let's run the following command on the gateway together .

```
ansible-playbook serviers/site.yml
```

* The point to note is that we use ansible-vault for our passwords. so change them with what you want :)

and over, You can use all the servers and enjoy .

## Concepts we learned in this project 

1. working with git for project management

2. monitoring basics

3. Prometheus exporters

4. visualization and dashboards

5. Time Series databases (Prometheus)

6. log management using elasticsearch

7. Alerting basics

8. writing custom Prometheus exporters

9. Automation using Ansible