FROM debian

RUN apt-get update -y
RUN apt-get install -y vim curl

# installing node
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

COPY startup.sh /root/startup.sh
RUN chmod +x /root/startup.sh
ENTRYPOINT []
CMD ["/root/startup.sh"]
