FROM mariadb:11.8.2

# 패키지 목록을 업데이트하고 mariadb-client를 설치합니다.
RUN apt-get update && \
    apt-get install -y mariadb-client && \
    rm -rf /var/lib/apt/lists/*
