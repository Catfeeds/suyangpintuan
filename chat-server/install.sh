#!/bin/bash
BASE_PATH=$(cd `dirname $0`; pwd)
SERVICE_NAME=chatserver


SCRIPT_PATH=${BASE_PATH}/start.php
LOG_PATH=${BASE_PATH}/${SERVICE_NAME}.log


if [ -n "$(grep 'Aliyun Linux release' /etc/issue)" -o -e /etc/redhat-release ]; then
  OS=CentOS
  [ -n "$(grep ' 7\.' /etc/redhat-release)" ] && CentOS_RHEL_version=7
  [ -n "$(grep ' 6\.' /etc/redhat-release)" -o -n "$(grep 'Aliyun Linux release6 15' /etc/issue)" ] && CentOS_RHEL_version=6
  [ -n "$(grep ' 5\.' /etc/redhat-release)" -o -n "$(grep 'Aliyun Linux release5' /etc/issue)" ] && CentOS_RHEL_version=5
elif [ -n "$(grep 'Amazon Linux AMI release' /etc/issue)" -o -e /etc/system-release ]; then
  OS=CentOS
  CentOS_RHEL_version=6
elif [ -n "$(grep 'bian' /etc/issue)" -o "$(lsb_release -is 2>/dev/null)" == "Debian" ]; then
  OS=Debian
  [ ! -e "$(which lsb_release)" ] && { apt-get -y update; apt-get -y install lsb-release; clear; }
  Debian_version=$(lsb_release -sr | awk -F. '{print $1}')
elif [ -n "$(grep 'Deepin' /etc/issue)" -o "$(lsb_release -is 2>/dev/null)" == "Deepin" ]; then
  OS=Debian
  [ ! -e "$(which lsb_release)" ] && { apt-get -y update; apt-get -y install lsb-release; clear; }
  Debian_version=$(lsb_release -sr | awk -F. '{print $1}')
# kali rolling
elif [ -n "$(grep 'Kali GNU/Linux Rolling' /etc/issue)" -o "$(lsb_release -is 2>/dev/null)" == "Kali" ]; then
  OS=Debian
  [ ! -e "$(which lsb_release)" ] && { apt-get -y update; apt-get -y install lsb-release; clear; }
  if [ -n "$(grep 'VERSION="2016.*"' /etc/os-release)" ]; then
    Debian_version=8
  else
    echo "${CFAILURE}Does not support this OS, Please contact the author! ${CEND}"
    kill -9 $$
  fi
elif [ -n "$(grep 'Ubuntu' /etc/issue)" -o "$(lsb_release -is 2>/dev/null)" == "Ubuntu" -o -n "$(grep 'Linux Mint' /etc/issue)" ]; then
  OS=Ubuntu
  [ ! -e "$(which lsb_release)" ] && { apt-get -y update; apt-get -y install lsb-release; clear; }
  Ubuntu_version=$(lsb_release -sr | awk -F. '{print $1}')
  [ -n "$(grep 'Linux Mint 18' /etc/issue)" ] && Ubuntu_version=16
elif [ -n "$(grep 'elementary' /etc/issue)" -o "$(lsb_release -is 2>/dev/null)" == 'elementary' ]; then
  OS=Ubuntu
  [ ! -e "$(which lsb_release)" ] && { apt-get -y update; apt-get -y install lsb-release; clear; }
  Ubuntu_version=16
else
  echo "${CFAILURE}Does not support this OS, Please contact the author! ${CEND}"
  kill -9 $$
fi

if [ "$(getconf WORD_BIT)" == "32" ] && [ "$(getconf LONG_BIT)" == "64" ]; then
  OS_BIT=64
  SYS_BIG_FLAG=x64 #jdk
  SYS_BIT_a=x86_64;SYS_BIT_b=x86_64; #mariadb
else
  OS_BIT=32
  SYS_BIG_FLAG=i586
  SYS_BIT_a=x86;SYS_BIT_b=i686;
fi

LIBC_YN=$(awk -v A=$(getconf -a | grep GNU_LIBC_VERSION | awk '{print $NF}') -v B=2.14 'BEGIN{print(A>=B)?"0":"1"}')
[ $LIBC_YN == '0' ] && GLIBC_FLAG=linux-glibc_214 || GLIBC_FLAG=linux

if uname -m | grep -Eqi "arm"; then
  armPlatform="y"
  if uname -m | grep -Eqi "armv7"; then
    TARGET_ARCH="armv7"
  elif uname -m | grep -Eqi "armv8"; then
    TARGET_ARCH="arm64"
  else
    TARGET_ARCH="unknown"
  fi
fi

THREAD=$(grep 'processor' /proc/cpuinfo | sort -u | wc -l)

# Percona
if [[ "${OS}" =~ ^Ubuntu$|^Debian$ ]]; then
  if [ "${Debian_version}" == '6' ]; then
    sslLibVer=ssl098
  else
    sslLibVer=ssl100
  fi
elif [ "${OS}" == "CentOS" ]; then
  if [ "${CentOS_RHEL_version}" == '5' ]; then
    sslLibVer=ssl098e
  else
    sslLibVer=ssl101
  fi
else
  sslLibVer=unknown
fi


if [ "${CentOS_RHEL_version}" == '7' ]; then
    SERVICE_PATH=/etc/systemd/system/${SERVICE_NAME}.service
    PHP_PATH=`which php`

    cat << EOF > ${SERVICE_PATH}
[Unit]
Description=${SERVICE_NAME} Server
After=network.target
After=syslog.target
After=redis.target

[Service]
Type=forking
PIDFile=${BASE_PATH}/${SERVICE_NAME}.pid
ExecStart=${PHP_PATH} ${SCRIPT_PATH} restart -d >> ${LOG_PATH} 2>&1
ExecStop=${PHP_PATH} ${SCRIPT_PATH} stop
ExecReload=${PHP_PATH} ${SCRIPT_PATH} reload
Restart=always

[Install]
WantedBy=multi-user.target graphical.target
EOF

systemctl enable ${SERVICE_NAME}.service
systemctl start ${SERVICE_NAME}.service

elif [ "${CentOS_RHEL_version}" == '6' ]; then
    PHP_PATH=`which php`
    SERVICE_PATH=/etc/init.d/${SERVICE_NAME}

    cat << EOF > ${SERVICE_PATH}
#!/bin/bash
# chkconfig: 2345 10 90
# description: chatserver service
PHP_PATH=`which php`

case "\$1" in
    start)
        \$PHP_PATH \$SCRIPT_PATH start -d
        ;;

    stop)
        \$PHP_PATH \$SCRIPT_PATH stop
        ;;

    status)
        \$PHP_PATH \$SCRIPT_PATH status
        ;;

    restart)
        \$PHP_PATH \$SCRIPT_PATH restart -d
        ;;
esac
EOF

    chmod 777 ${SERVICE_PATH}
    chkconfig ${SERVICE_NAME} on

    sleep 1
    service ${SERVICE_NAME} restart
fi