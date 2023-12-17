```bash
export USR=userName
ssh ${USR}@192.168.56.10 mkdir ssl-cert/
cd ssl-cert/
sftp ${USR}@192.168.56.10:ssl-cert
put *

ssh ${USR}@192.168.56.10:ssl-cert
sudo cp ca.pem /root/.docker/
sudo cp server.key /root/.docker/key.pem
sudo cp server.pem /root/.docker/cert.pem

cat client.pem >> cert-and-key.pem
cat client.key >> cert-and-key.pem
curl -vv --cacert ca.pem --cert cert-and-key.pem https://192.168.56.10:2376/version
```